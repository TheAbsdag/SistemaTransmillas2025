(function () {
  const estructuraCampos = {
    "APARTAMENTO": [
      { label: "NUMERO APARTAMENTO", name: "apartamento_num", type: "number" },
      { label: "MANZANA", name: "manzana" },
      { label: "BLOQUE", name: "bloque" },
      { label: "UNIDAD", name: "unidad" },
      { label: "INTERIOR", name: "interior" },
      { label: "TORRE", name: "torre" }
    ],
    "OFICINA": [
      { label: "NUMERO OFICINA", name: "oficina_num", type: "number" },
      { label: "EDIFICIO", name: "edificio" },
      { label: "MANZANA", name: "manzana" },
      { label: "BLOQUE", name: "bloque" },
      { label: "UNIDAD", name: "unidad" },
      { label: "INTERIOR", name: "interior" },
      { label: "TORRE", name: "torre" }
    ],
    "CASA": [
      { label: "NUMERO DE CASA", name: "casa_num", type: "number" },
      { label: "CONJUNTO", name: "conjunto" }
    ],
    "EMPRESA": [
      { label: "NUMERO DE LOCAL", name: "local_num", type: "number" },
      { label: "PISO", name: "piso" },
      { label: "SOTANO", name: "sotano" },
      { label: "LOCAL CALLE", name: "localcalle" }
    ],
    "CONSULTORIO": [
      { label: "NUMERO CONSULTORIO", name: "consultorio_num", type: "number" },
      { label: "PISO", name: "piso" },
      { label: "SOTANO", name: "sotano" },
      { label: "LOCAL CALLE", name: "localcalle" }
    ],
    "CENTRO EMPRESARIAL": [
      { label: "NUMERO DE LOCAL", name: "local_num", type: "number" },
      { label: "BODEGA", name: "bodega" },
      { label: "SOTANO", name: "sotano" },
      { label: "LOCAL CALLE", name: "localcalle" }
    ],
    "CENTRO COMERCIAL": [
      { label: "NUMERO DE LOCAL", name: "local_num", type: "number" },
      { label: "PISO", name: "piso" },
      { label: "SOTANO", name: "sotano" },
      { label: "LOCAL CALLE", name: "localcalle" }
    ]
  };

  function byId(id) {
    return document.getElementById(id);
  }

  function setValue(id, value) {
    const el = byId(id);
    if (el) el.value = value || "";
  }

  function showSpinner(id) {
    const el = byId(id);
    if (el) el.style.display = "block";
  }

  function hideSpinner(id) {
    const el = byId(id);
    if (el) el.style.display = "none";
  }

  function fetchCliente(tipo, valor) {
    return fetch("../../buscarClientesW.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: new URLSearchParams({ vlores: valor, tipo: tipo })
    }).then(function (res) { return res.json(); });
  }

  function parseDireccionBase(texto) {
    const result = { base: "", hash: "", guion: "" };
    if (!texto) return result;

    let base = texto;
    let hash = "";
    let guion = "";

    if (base.indexOf("#") >= 0) {
      const splitHash = base.split("#");
      base = (splitHash[0] || "").trim();
      hash = splitHash.slice(1).join("#").trim();
    }

    if (hash.indexOf("-") >= 0) {
      const splitGuion = hash.split("-");
      hash = (splitGuion[0] || "").trim();
      guion = splitGuion.slice(1).join("-").trim();
    } else if (texto.indexOf("#") < 0 && base.indexOf("-") >= 0) {
      const splitGuionBase = base.split("-");
      base = (splitGuionBase[0] || "").trim();
      guion = splitGuionBase.slice(1).join("-").trim();
    }

    result.base = base;
    result.hash = hash;
    result.guion = guion;
    return result;
  }

  function createComplementBuilder(selectId, containerId, hiddenId, suffix) {
    return function buildComplement() {
      const select = byId(selectId);
      const hidden = byId(hiddenId);
      if (!select || !hidden) return;

      const tipo = select.value;
      const fields = estructuraCampos[tipo] || [];
      const parts = [];

      fields.forEach(function (field) {
        const check = byId(field.name + "_check_" + suffix);
        const input = document.querySelector('[name="' + field.name + '_' + suffix + '"]');
        if (check && check.checked && input && input.value.trim() !== "") {
          parts.push(field.label + " " + input.value.trim());
        }
      });

      hidden.value = parts.join(" & ");
    };
  }

  function createComplementField(field, suffix, onChange) {
    const wrap = document.createElement("div");
    wrap.className = "d-flex align-items-center mb-2 gap-2";

    const check = document.createElement("input");
    check.type = "checkbox";
    check.className = "form-check-input";
    check.id = field.name + "_check_" + suffix;

    const label = document.createElement("label");
    label.className = "form-check-label me-2";
    label.setAttribute("for", check.id);
    label.textContent = field.label;

    const input = document.createElement("input");
    input.type = field.type || "text";
    input.name = field.name + "_" + suffix;
    input.className = "form-control";
    input.style.maxWidth = "140px";
    input.disabled = true;

    check.addEventListener("change", function () {
      const enabled = !!this.checked;
      input.disabled = !enabled;
      input.required = enabled;
      if (!enabled) input.value = "";
      onChange();
    });

    input.addEventListener("input", onChange);

    wrap.appendChild(check);
    wrap.appendChild(label);
    wrap.appendChild(input);

    return wrap;
  }

  function applyComplementData(fields, extras, suffix, onChange) {
    extras.forEach(function (extra) {
      const text = (extra || "").trim().toUpperCase();
      if (!text) return;

      fields.forEach(function (field) {
        if (text.indexOf(field.label) !== 0) return;

        const value = text.replace(field.label, "").trim();
        const check = byId(field.name + "_check_" + suffix);
        const input = document.querySelector('[name="' + field.name + '_' + suffix + '"]');

        if (check && input) {
          check.checked = true;
          input.disabled = false;
          input.value = value;
        }
      });
    });

    onChange();
  }

  function setupComplement(selectId, containerId, hiddenId, suffix) {
    const select = byId(selectId);
    const container = byId(containerId);
    const hidden = byId(hiddenId);
    if (!select || !container || !hidden) return { build: function () {}, render: function () {} };

    const build = createComplementBuilder(selectId, containerId, hiddenId, suffix);

    function render(extras) {
      const tipo = select.value;
      const fields = estructuraCampos[tipo] || [];

      container.innerHTML = "";
      hidden.value = "";

      fields.forEach(function (field) {
        container.appendChild(createComplementField(field, suffix, build));
      });

      if (extras && extras.length) {
        applyComplementData(fields, extras, suffix, build);
      }
    }

    select.addEventListener("change", function () { render(); });

    return { build: build, render: render };
  }

  function cargarRemitente(data, complemento) {
    setValue("param6", data.cli_nombre);
    setValue("param2", data.cli_telefono);
    setValue("param4", data.cli_idciudad);
    setValue("id_param", data.idclientes);
    setValue("id_param2", data.idclientesdir);
    setValue("id_param1", 1);

    if (!data.cli_direccion) return;

    const dir = data.cli_direccion.split("&");
    const parsed = parseDireccionBase(dir[1] || "");

    setValue("param5", dir[0]);
    setValue("param5", dir[0]);
    const tipoViaR = byId("param5");
    if (tipoViaR) tipoViaR.dispatchEvent(new Event("change"));
    setValue("dir1R", parsed.base);
    setValue("dir2R", parsed.hash);
    setValue("dir3R", parsed.guion);
    setValue("param19", dir[2]);
    setValue("param23", dir[4]);

    complemento.render(dir.slice(5));
  }

  function limpiarRemitente(complemento) {
    ["param6", "param4", "param5", "dir1R", "dir2R", "dir3R", "param19", "param23", "id_param", "id_param2"].forEach(function (id) {
      setValue(id, "");
    });
    setValue("id_param1", 0);
    complemento.render();
  }

  function cargarDestinatario(data, complemento) {
    setValue("param9", data.cli_nombre);
    setValue("param11", data.cli_idciudad);
    setValue("id_param0", data.idclientesdir);

    if (!data.cli_direccion) return;

    const dir = data.cli_direccion.split("&");
    const parsed = parseDireccionBase(dir[1] || "");

    setValue("param10", dir[0]);
    const tipoViaD = byId("param10");
    if (tipoViaD) tipoViaD.dispatchEvent(new Event("change"));

    setValue("dir1D", parsed.base);
    setValue("dir2D", parsed.hash);
    setValue("dir3D", parsed.guion);
    setValue("param21", dir[2]);
    setValue("param24", dir[4]);

    complemento.render(dir.slice(5));
  }

  function limpiarDestinatario(complemento) {
    ["param9", "param11", "param10", "dir1D", "dir2D", "dir3D", "param21", "param24", "id_param0"].forEach(function (id) {
      setValue(id, "");
    });
    complemento.render();
  }

  function debounce(fn, wait) {
    let timeout;
    return function () {
      const args = arguments;
      clearTimeout(timeout);
      timeout = setTimeout(function () {
        fn.apply(null, args);
      }, wait);
    };
  }

  function setupMayusculas(formId) {
    const form = byId(formId);
    if (!form) return;

    form.addEventListener("input", function (event) {
      const el = event.target;
      if (!el || (el.tagName !== "INPUT" && el.tagName !== "TEXTAREA")) return;

      const skip = ["email", "password", "number", "date", "datetime-local", "hidden", "checkbox", "file"];
      if (skip.indexOf(el.type) >= 0) return;

      if (typeof el.selectionStart === "number") {
        const start = el.selectionStart;
        const end = el.selectionEnd;
        el.value = el.value.toUpperCase();
        el.setSelectionRange(start, end);
      } else {
        el.value = el.value.toUpperCase();
      }
    });
  }

  function setupOficinaTransmillas() {
    function bind(tipoViaId, ids) {
      const tipoVia = byId(tipoViaId);
      if (!tipoVia) return;

      const campos = ids.map(byId).filter(Boolean);
      const update = function () {
        const isOficina = (tipoVia.value || "").trim().toUpperCase() === "OFICINA TRANSMILLAS";
        campos.forEach(function (campo) {
          if (isOficina) campo.removeAttribute("required");
          else campo.setAttribute("required", "required");
        });
      };

      tipoVia.addEventListener("change", update);
      update();
    }

    bind("param5", ["dir1R", "dir2R", "dir3R"]);
    bind("param10", ["dir1D", "dir2D", "dir3D"]);
  }

  function resolveEndpoint(form) {
    const custom = form.getAttribute("data-endpoint");
    if (custom && custom.trim() !== "") return custom.trim();
    return "nueva_plataforma/controller/SolicitudWhatsAppController.php";
  }

  function toggleSubmit(form, disabled) {
    const btn = form.querySelector('button[type="submit"]');
    if (!btn) return;
    btn.disabled = !!disabled;
  }

  function buildCreditoOption(value, label) {
    const option = document.createElement("option");
    option.value = value;
    option.textContent = label;
    return option;
  }

  document.addEventListener("DOMContentLoaded", function () {
    const form = byId("formSolicitud");
    if (!form) return;

    const complementoR = setupComplement("param19", "camposComplemento", "complementoFinal", "R");
    const complementoD = setupComplement("param21", "camposComplementoD", "complementoFinalD", "D");

    complementoR.render();
    complementoD.render();

    const buscarRemPorDoc = debounce(function (value) {
      if (!value || value.length < 7) return;
      showSpinner("spinner-remitente");

      fetchCliente("documento", value)
        .then(function (data) {
          hideSpinner("spinner-remitente");
          if (!data) {
            limpiarRemitente(complementoR);
            return;
          }
          cargarRemitente(data, complementoR);
        })
        .catch(function () {
          hideSpinner("spinner-remitente");
          limpiarRemitente(complementoR);
        });
    }, 600);

    const buscarRemPorTel = function (value) {
      if (!value || value.length < 7) return;
      showSpinner("spinner-remitente");

      fetchCliente("telefono", value)
        .then(function (data) {
          hideSpinner("spinner-remitente");
          if (!data) {
            limpiarRemitente(complementoR);
            return;
          }
          cargarRemitente(data, complementoR);
        })
        .catch(function () {
          hideSpinner("spinner-remitente");
          limpiarRemitente(complementoR);
        });
    };

    const buscarDest = debounce(function (value) {
      if (!value || value.length < 7) return;
      showSpinner("spinner-destinatario");

      fetchCliente("telefono", value)
        .then(function (data) {
          hideSpinner("spinner-destinatario");
          if (!data) {
            limpiarDestinatario(complementoD);
            return;
          }
          cargarDestinatario(data, complementoD);
        })
        .catch(function () {
          hideSpinner("spinner-destinatario");
          limpiarDestinatario(complementoD);
        });
    }, 600);

    const inputDocumento = byId("param1");
    const inputTelRem = byId("param2");
    const inputTelDest = byId("param8");
    const bloqueCreditos = byId("bloqueCreditosAsociados");
    const mensajeCreditos = byId("mensajeCreditos");
    const selectCredito = byId("cliente_credito");

    function ocultarCreditos() {
      if (bloqueCreditos) bloqueCreditos.classList.add("d-none");
      if (selectCredito) {
        selectCredito.innerHTML = "";
        selectCredito.appendChild(buildCreditoOption("", "Seleccione..."));
      }
      if (mensajeCreditos) {
        mensajeCreditos.textContent = "No hay creditos asociados para los telefonos ingresados.";
      }
    }

    function mostrarCreditos(creditos) {
      if (!selectCredito || !bloqueCreditos || !mensajeCreditos) return;

      selectCredito.innerHTML = "";
      selectCredito.appendChild(buildCreditoOption("", "Seleccione..."));

      creditos.forEach(function (credito) {
        const id = credito.idcreditos || "";
        const nombre = credito.cre_nombre || ("Credito " + id);
        selectCredito.appendChild(buildCreditoOption(id, nombre));
      });

      const total = creditos.length;
      mensajeCreditos.textContent = "Este contacto tiene " + total + " credito(s) asociado(s).";
      bloqueCreditos.classList.remove("d-none");
    }

    const consultarCreditosAsociados = debounce(function () {
      const telRem = (inputTelRem ? inputTelRem.value : "").trim();
      const telDes = (inputTelDest ? inputTelDest.value : "").trim();

      if (telRem.length < 7 && telDes.length < 7) {
        ocultarCreditos();
        return;
      }

      fetch(resolveEndpoint(form), {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: new URLSearchParams({
          accion: "obtenerCreditosAsociados",
          telremitente: telRem,
          teldestino: telDes
        })
      })
        .then(function (res) { return res.json(); })
        .then(function (data) {
          if (!data || !data.ok || !Array.isArray(data.creditos) || data.creditos.length === 0) {
            ocultarCreditos();
            return;
          }

          mostrarCreditos(data.creditos);
        })
        .catch(function () {
          ocultarCreditos();
        });
    }, 350);

    if (inputDocumento) {
      inputDocumento.addEventListener("keyup", function () {
        buscarRemPorDoc(inputDocumento.value.trim());
      });
    }

    if (inputTelRem) {
      inputTelRem.addEventListener("blur", function () {
        buscarRemPorTel(inputTelRem.value.trim());
        consultarCreditosAsociados();
      });
    }

    if (inputTelDest) {
      inputTelDest.addEventListener("keyup", function () {
        buscarDest(inputTelDest.value.trim());
        consultarCreditosAsociados();
      });
      inputTelDest.addEventListener("blur", consultarCreditosAsociados);
    }

    if (selectCredito && mensajeCreditos) {
      selectCredito.addEventListener("change", function () {
        if (!this.value) return;
        const texto = this.options[this.selectedIndex] ? this.options[this.selectedIndex].text : "";
        mensajeCreditos.textContent = "Credito seleccionado: " + texto;
      });
    }

    setupMayusculas("formSolicitud");
    setupOficinaTransmillas();
    ocultarCreditos();

    form.addEventListener("submit", function (event) {
      event.preventDefault();

      if (!form.checkValidity()) {
        form.reportValidity();
        return;
      }

      const token = byId("token");
      // if (token && !token.value.trim() && window.Swal) {
      //   Swal.fire({
      //     icon: "warning",
      //     title: "Enlace invalido",
      //     text: "Abre el formulario desde el enlace oficial de WhatsApp."
      //   });
      //   return;
      // }

      const endpoint = resolveEndpoint(form);
      const formData = new FormData(form);
      formData.append("accion", "guardarSolicitudWhatsApp");

      toggleSubmit(form, true);

      fetch(endpoint, {
        method: "POST",
        body: formData
      })
        .then(function (res) { return res.json(); })
        .then(function (data) {
          if (!data || !data.ok) {
            const msg = (data && data.mensaje) ? data.mensaje : "No se pudo enviar la solicitud.";
            if (window.Swal) {
              Swal.fire({ icon: "error", title: "Error", text: msg });
            } else {
              alert(msg);
            }
            return;
          }

          const guia = data.guia || data.planilla || "Pendiente";
          if (window.Swal) {
            Swal.fire({
              icon: "success",
              title: "Solicitud enviada",
              text: "Tu solicitud fue registrada. Guia: " + guia
            }).then(function () {
              form.reset();
              complementoR.render();
              complementoD.render();
            });
          } else {
            alert("Solicitud enviada. Guia: " + guia);
            form.reset();
            complementoR.render();
            complementoD.render();
          }
        })
        .catch(function () {
          if (window.Swal) {
            Swal.fire({
              icon: "error",
              title: "Error",
              text: "Error de conexion con el servidor."
            });
          } else {
            alert("Error de conexion con el servidor.");
          }
        })
        .finally(function () {
          toggleSubmit(form, false);
        });
    });
  });
})();
